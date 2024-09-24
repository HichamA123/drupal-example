# Unc Inc Rest Assessment - Drupal

This package contains the assessment for a Drupal project

## Requirements

- Composer 1.x
- PHP 7.2.5+
- SQLite 3.8.8+
- PHP Code Sniffer
- SQLitebrowser (or similar)
- Git (preferably command line)
- Your editor of choice

## Installation

The directory contains a working setup of Drupal with local SQLite database storage (located in sites/default/files). To setup the project do:

Install all packages:
```
$ composer install
```

Start a local webserver through drush:

```
$ vendor/drush/drush/drush rs 8000
```

As given as output by the above command you can now view the site by going to http://127.0.0.1:8000 in your browser.


## Usage

To use any drush command just use the vendor command from with the drupal directory while the local webserver is running.

e.g to login into Drupal an easy way is to use the one-time login link
```
$ vendor/drush/drush/drush uli
```
Paste the part after default behind your URL in your browser, e.g http://127.0.0.1:8000/user/reset/1/1610534014/pofD2p84yE8TASyo7uoAisSjvpJXXoVUSiMsozNTI08/login (note this login only works one time)

We already created an API endpoint on the URL 127.0.0.1:8000/nodes/list

You can confirm that the API endpoint is working, run the following command(s):

List all TODOs:
```
curl --location --request GET 'http://127.0.0.1:8000/nodes/list' --header 'Accept: application/json'
```
The output should be [TODO]



## Assignment

Edit the existing endpoint so it returns nodes of all types including paginating (and optional type filter).
Currently, this install contains no nodes and no content types. It is up to you to create them and give them fields (minimum of 3 with one being a long text)

## Rules

There is a small set of rules we would like you to adhere to:

- The project needs to be versioned using git. Please make us of a local git repository to keep track of your changes (You need to start a repo by using `git init`). We try to adhere to Conventional Commits as much as we can.
- Try to adhere to standards as much as you can. Use conventions set by the framework(s). Your code should follow the coding standards.
- Do not write unnecessary code. Use packages or libraries where you can.
- Do not spend too much time on this. Think practically, act as if a client is waiting for your changes.
- If you can not finish the assignment within a reasonable amount of time, just write down your train of thoughts and steps you would like to take. We are looking more into your approach, not so much perfect code.

## Hints

Some hints to help you along the way:

- We are looking for a rest endpoint which might be used in a headless setup, so think what would be ideal to get returned. (flexibility, server load, payload etc)
- We are looking more for your thoughts so take us through your thought process
- The assignment should not take more than about a couple of hours

## Submission

Create an archive of the full project, including the `.git` folder and database and either email it or transfer it using e.g. WeTransfer or ToffeeShare.
