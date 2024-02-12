# SmartPush Test

## Context

This test has been created to mesure performance to create an API with Symfony and front with ReactJS

## Stack project
### API
- Project API has been created with Symfony 6.4 and docker
- Following dependencies has been added on project :   
orm / serializer / nelmio-api-doc bundle
### Front
- Project Front has been created with ReactJS and node 18

## Launch project
### Clone project
`git clone https://github.com/Smart-Push/smartpush-test.git`

### Launch stack docker
- On root folder, launch `docker-compose up -d --build`
- Connect to container docker www and launch `composer install`

### Launch front
1. Go to `front` folder
2. Launch command `npm install && npm start`   

### Url projects
- Front : `http://localhost:3000`
- API : `http://localhost` or `https://localhost

## Expected objectives
### MCD
![Alt text](api/docs/MCD.png?raw=true "Title")

### API : 
1. Implement MCD
For this point, please use doctrine migration
2. Create an additional migration to insert datas located in `default_data` folder
3. Create five endpoints API with following specification
![Alt text](api/docs/Specs_api.png?raw=true "Title")
4. For each route, define API documentation with openapi specification and nelmio api doc

Don't forget :
- Use serialization group
- Good respect of REST standard

### Front :

1. On front, you must consume API to create a table with react.
2. Table list of transactions entries
3. Add input to filter by label / amount or transaction_type
4. Add button to submit filter
5. An API call should be made as soon as we press the submit button
6. Clicking on a row in the table should display the transaction details below the table

Don't forget :
- Catch any error occured on API call
- Display a text loader during API call
- The project was created in Typescript, so we expect to find the advantages of using typescript.
- For API calls, you have the freedom to use fetch, axios or another library
