---
title: Database Migrations with CodeIgniter
slug: database-migrations-with-codeigniter
abstract: You version control your source-code, why not your database?
date: 8th June 2012
---

I first became aware of database migrations a few years ago when I was exploring the world of [Rails](http://rubyonrails.org/).
However, it has not been to recently, with a gentle nudge from SE-Radio ([Episode 186](http://www.se-radio.net/2012/06/episode-186-martin-fowler-and-pramod-sadalage-on-agile-database-development/)) and a large web application build that they have re-entered my development lifecycle.
As a result of current events I for one do not wish them to leave any time soon.

### So what are Database Migrations?

As any common web application in some way not being driven by a database is extremely rare, it is incredibly important to keep the state of the schema in synchronisation with the source code.
Migrations are used to achieve this goal, providing you with the ability to in a matter of speaking, version control your schema alterations.
Using this ideology, developers are able to 'pull down' schema changes along with source code alterations (if using some form of [SCM](http://en.wikipedia.org/wiki/Source_Control_Management)), without the need to run external tear up/down scripts.
As a result of this it allows you to quickly roll back and forth through the history of the schema, so as to work with desired version.
These migrations are commonly used to easily alter state based on the environment you are in, for example, development, testing/QA or production.

### An example...

I have spent more and more time using CodeIgniter (and as a result PHP) over the past few weeks.
This change coincided with my interest in migrations very well, as I was pleasantly suprised that CodeIgniter provides (though very simple) a [database migration implementation](http://codeigniter.com/user_guide/libraries/migration.html) out of the box.
It should be noted that there are many different migration tools available in most langauges conjured up.
Due to this please be warned of becoming vendor locked and spend sometime in making your decision.

Below is a sample migration that should be created inside './application/migrations/' with the filename '001-create-users.php'.
Migration files in CodeIgniter follow the conversion of putting the version number and then a description (commonly the class name).

~~~ .php
class Migration_Create_Users extends CI_Migration {

  public function up()
  {
    $fields = array(
      'id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
      'username VARCHAR(10) DEFAULT NULL',
      'password VARCHAR(50) DEFAULT NULL'
    );

    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('users');
  }

  public function down()
  {
    $this->dbforge->drop_table('users');
  }

}
~~~

The code snippet above when created, adds a table (in MySQL) with an auto incrementing primary key called 'id' and 'username'/'password' columns.
If this migration is 'teared down' however the the 'user' table is dropped from the schema.
To run this migration you must first make sure that they are enabled and desired version set in your application's configuration file (found at './application/config/migration.php').
Once configured you can create a simple controller, like the one displayed below, which when visited calls the migration library.

~~~ .php
class Migrate extends CI_Controller {

  public function index()
  {
    $this->load->library('migration');

    if ( ! $this->migration->current()) {
      show_error($this->migration->error_string());
    }
  }

}
~~~

Adding a second schema (called '002-add-name-fields.php') to the application you can see how the database can be procedurally altered.

~~~ .php
class Migration_Add_Name_Fields extends CI_Migration {

  public function up()
  {
    $fields = array(
      'first_name VARCHAR(50) DEFAULT NULL',
      'last_name VARCHAR(50) DEFAULT NULL'
    );

    $this->dbforge->add_column('users', $fields);
  }

  public function down()
  {
    $this->dbforge->drop_column('users', 'first_name');
    $this->dbforge->drop_column('users', 'last_name');
  }

}
~~~

As you can see from looking at the two migration examples, switching between versions is incredibly simple.
The simplicity comes from the creation of well thought out tear up (creation) and tear down (deletion) methods.
Stepping back into the migration ethos I can definitely appreciate the benefits of managing schemas in this manner.