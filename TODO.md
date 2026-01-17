# To-Do

## API

### Recipes

#### GET

- [x] random recipe 
- [ ] n best recipes 
- [ ] total of tag 
- [ ] recipe search (text, min/max time (number or enum range), difficulty, cost, tags)
- [ ] get recipe 
- [ ] get recipe image 

#### POST
- [x] create recipe (title, description, image, tags[], difficulty, prepTime, cost, servings, ingredientsQuantity[], ingredientsName[], steps[])
- [ ] update recipe (if creator or admin) ?

### Users

#### GET

- [x] login
- [x] get user
- [x] get user image 
- [x] get user saves
- [x] get user recipes

#### POST
- [x] logout
- [x] register (username, email, password)
- [x] update image (if logged user)
- [x] delete image (if logged user)
- [x] save recipe (if logged user)
- [x] unsave recipe (if logged user)
- [ ] make admin (if admin) ?
- [ ] delete user (if admin) ?

### Reviews

#### GET

- [ ] recipe reviews

#### POST

- [ ] create reviews (userId, recipeId, reviewText, rating)
- [ ] delete review (if creator or admin)


### About

#### GET

- [ ] total users
- [ ] total recipes
- [ ] total avg rating
- [ ] one

### Chat

#### GET

- [ ] get messages

#### POST

- [ ] send message
- [ ] clear

## Pages
- [ ] COLORS 
- [ ] admin page + logged modify
### Home
- [x] switch pages when searching
    - [x] search
    - [x] tag
- [ ] random recipe
#### Single Recipe
- [ ] open single recipe
    - [x] link on all the card surface (only title)
- [ ] getting card from db

### About
- [ ] add useless API

### Recipes
- [ ] add search
- [ ] hide/show filters instead of search button
- [ ] ajax (maybe) --> accessibility

### Profile
- [ ] delete image?
- [ ] fix image input (missing submit)
- [ ] handle generic/logged user difference