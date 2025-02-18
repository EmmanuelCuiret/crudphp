[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/y60cxQ0S)

# PHP-CRUD

## Create, read, update and delete

### 🌱 Must-have features

As a kid (or even now), chances are you liked collecting things. Seashells, Pokemon cards, stamps, ... While waiting for our collections to be (even more) popular and thus making our collections skyrocket in value, it can be interesting to build a site to make an inventory of your collection.

> 💡 There's a lot to forms: validation, styling or retaining data when validation fails. Doing all this can be time consuming, so focus any the logic first for this exercise.

A typical CRUD implementation allows the user or admin to create, read, update and delete data and will typically be used for any systems relying on dynamic data.

And what is the best place to store datas? A Database!

#### Preparation

- Think of a collection you'd like to have an inventory for.
- What information is interesting to keep track of for that kind of objects?
- Prepare the database structure to contain this info, and add some quick dummy content.

> For this exercise you can have only one table.

- For the connexion to the DB, set the parameters in the `config.php` and use the file `connexion.php`.

#### Step 1: read V

- On the index page, build an overview of everything in the collection (you will find some provided code that's helpful for this)
- Add the index page to the menu.
- Once an item is clicked, a detail page is loaded --> `single.php`. It uses to display the content of a single element of your DB.

#### Step 2: create V

- Use the `create.php` file.
- Build a form that contains all the relevant fields
- Once the form is submitted, save this info as a new entry in the database
- In a real application, you'd validate all of the data. So do it.
- Add the create page to the menu.

#### Step 3: update V

- Make a new page `edit.php`
- When clicking on an edit link for an entry, the edit page is loaded for that specific item
- When the edit form is submitted, the relevant entry in the database is updated

#### Step 4: delete V

- Build a link to `delete.php` that will delete a specific product from the database
- Afterwards, it will redirect to the overview

#### Step 5: login/session

For this part, you have to create a login system for your app.

- **Database**:
  - First of all, in your database, create a new table call `users`. A user must have at least an email, a login name and a password.
- **PHP**:
  - Use the `subscription.php` to register new users. Be careful about the password.
  - Use the `login.php` to log user. You'll to check the password.
  - Use the `logout.php` to log out an user.
  - For the session, you will need to use `$_SESSION` super global. The session should be checked on every pages.
  - If a user is not log in, the visitor can only read the datas (not create, update, delete).

### 🌼 Nice to have

- Check who might need your help. Maybe your discoveries can already prove valuable to your peers?
- Pair up with someone: what difference do you have in your approach? Discuss and look for opportunities to further improve your code.
- What interesting data can you include for your collection: images or fetch some stuff from an API - found something? Go for it!

### 🌳 Nice to have (hard)

V- Create a filter for your collection:
- Update your database: instead of a simple list of items, give every item in your list their own properties. (E.g.: add element type to your Pokemon)
- Have a filter bar that will display whatever has been selected (if not, it shows the whole list)
V- Timestamp changes and use soft deletes instead of permanent deletions:
  V- Add updated_at as a new column to your table.
  V- Whenever you update an item on your list, make sure a new timestamp is saved in updated_at as well.
  V- Upon deleting an item from your list, instead of removing the item from the database, check how you can apply soft deletes in your application.

![crud](crud.gif)

Sorry, best I could find...
