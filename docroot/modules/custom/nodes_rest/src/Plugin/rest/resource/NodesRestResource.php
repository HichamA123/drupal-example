<?php

namespace Drupal\nodes_rest\Plugin\rest\resource;

use Psr\Log\LoggerInterface;
use Drupal\rest\ResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a resource to get the results of a view.
 *
 * @RestResource(
 *   id = "nodes_rest_list",
 *   label = @Translation("Nodes listing"),
 *   serialization_class = "Drupal\node\Entity\Node",
 *   uri_paths = {
 *     "canonical" = "/nodes/list"
 *   }
 * )
 */
class NodesRestResource extends ResourceBase {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    EntityTypeManagerInterface $entity_type_manager,
    RequestStack $request_stack
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->entityTypeManager = $entity_type_manager;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('entity_type.manager'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function get() {

    // Get the current request
    $request = $this->requestStack->getCurrentRequest();

    // Get query parameters
    $query_params = $request->query->all();
    $type = isset($query_params['type']) ? $query_params['type'] : NULL;
    $page = isset($query_params['page']) ? (int)$query_params['page'] : 0;

    $limit = 10; // Set how many items to return per page

    // Build the query
    $query = $this->entityTypeManager->getStorage('node')->getQuery();

    if ($type) {
      $query->condition('type', $type);
    }

    // Add pagination
    $query->range($page * $limit, $limit);
    $nids = $query->execute(); // only querying necessary node id's

    // Load nodes
    /** @var \Drupal\node\NodeInterface[] $nodes */  // lets the IDE know of the type of $nodes (for magic methods)
    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
    $output = [];

    foreach ($nodes as $node) {
      $type = $node->getType();

      // Initialize node output based on the type
      $node_data = [
          'id' => $node->id(),
          'type' => $type,
      ];

      // Customize output fields based on the content type and only getting necessary data
      switch ($type) {
        case 'blog_post':
          // print_r($node);
          $node_data['title'] = $node->get('title')->value;
          $node_data['author'] = $node->get('field_author')->value;
          $node_data['content'] = $node->get('field_content')->value;
          break;
          
          case 'event':
            $node_data['title'] = $node->get('title')->value;
            $node_data['event_date'] = $node->get('field_event_date')->value;
            $node_data['location'] = $node->get('field_location')->value;
            break;

        default:
            // Handle other types or default case if needed
            $node_data['title'] = $node->getTitle(); // Fallback to node title]
            break;
      }

      $output[] = $node_data;
    }

    $response = new ResourceResponse($output, 200);
    $cacheability = new CacheableMetadata();
    $cacheability->addCacheContexts(['node:list']);
    $response->addCacheableDependency($cacheability);

    return $response;
  }

}
