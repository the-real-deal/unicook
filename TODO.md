# To-Do

## API

### Recipes

#### GET

- [ ] random recipe 
- [ ] n best recipes 
- [ ] total of tag 
- [ ] recipe search (text, min/max time (number or enum range), difficulty, cost, tags)
- [ ] get recipe 
- [ ] get recipe image 

#### POST
- [ ] create recipe (title, description, image, tags[], difficulty, prepTime, cost, servings, ingredientsQuantity[], ingredientsName[], steps[])
- [ ] update recipe (if creator or admin)

### Users

#### GET

- [ ] login
- [ ] get user
- [ ] get user image 
- [ ] get user saves
- [ ] get user recipes

#### POST
- [ ] logout
- [ ] register (username, email, password)
- [ ] update image (if logged user)
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
