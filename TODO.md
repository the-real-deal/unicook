# To-Do

## API

### Recipes

#### GET

- [x] random recipe 
- [x] n best recipes
- [x] recipe rating 
- [x] total of tag 
- [x] recipe search (query, minPrepTime, maxPrepTime, difficulty, cost, tags, from, n)
- [x] get recipe 
- [x] get recipe image 
- [x] get recipe steps
- [x] get recipe ingredients
- [x] get recipe tags

#### POST
- [x] create recipe (title, description, image, tags[], difficulty, prepTime, cost, servings, ingredientsQuantity[], ingredientsName[], steps[])
- [x] update recipe (if creator or admin) ?
- [x] delete recipe (if creator or admin) ?

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
- [x] make admin (if admin) ?
- [x] delete user (if admin) ?

### Reviews

#### GET

- [x] get review
- [x] recipe reviews

#### POST

- [x] create reviews (userId, recipeId, body, rating)
- [x] delete review (if creator or admin)


### About

#### GET

- [x] total users
- [x] total recipes
- [x] total avg rating
- [x] one

### Chat

#### GET

- [x] get messages

#### POST

- [x] send message
- [x] clear

## Pages
- [ ] add error notification on every page
- [ ] COLORS 
- [ ] admin page + logged modify
### Home
- [x] switch pages when searching
    - [x] search
    - [x] tag
- [X] random recipe
#### Single Recipe
- [ ] open single recipe
    - [x] link on all the card surface (only title)
- [X] getting card from db

### About
- [X] add useless API

### Recipes
- [X] add search
- [ ] hide/show filters instead of search button
- [X] ajax (maybe) --> accessibility

### Profile
- [ ] delete image?
    - [X] add delete button
- [X] fix image input (missing submit)
- [ ] handle generic/logged user difference
    - [x] perchè se tolgo il form non carica più le immagini T_T

### pianinator
- [X] remove js inline
- [X] remove style inline
- [ ] check js (pianini)