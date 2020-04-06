# About this project

This is a non-profit project.
The portal is an open-source project that was created in collaboration with the Shopware community to help local retailers in this trying time.

It allows local governments, cities, or similar authorities to give local merchants an easy way of
keeping in touch with their customers and selling goods and services online.

After installing the project on a web server, local authorities can register within the portal.

After registration and activation by the portal owner, a sub-portal will be automatically created for each local authority.
After this step, local retailers can register within their local authority's sub-portal.
Next, customers can register and interact with retailers in their area.

[![Tweet](https://img.shields.io/twitter/url/http/shields.io.svg?style=social)](https://twitter.com/intent/tweet?text=Support%20your%20local%20merchants%21&url=https%3A%2F%2Fgithub.com%2FshopwareDowntown%2Fportal&via=ShopwareDevs&hashtags=Shopware6,community)

## Goal

At the time this project is created, the [COVID-19](https://en.wikipedia.org/wiki/Coronavirus_disease_2019) pandemic
has a serious impact on the economy. Since retail stores are forced to close, they now need new solutions to generate
an income.

The portal project is trying to help. It is created to serve the project [downtowns.io](https://downtowns.io/), but since
it is open source anyone is able to set up a a web server and provide the same service as downtowns to their local community. 

## How it works

A picture is worth a thousand words, so here are some example screen designs of the portal. The text is in german,
since the portal was initially developed for the german market.

![The registration page](readme_sc_registration.jpg?raw=true "The registration page") 

After the registration, the organisation has to be activated by the portal owner. Then these steps follow:


- An organisation (e.g. a city) registers within the portal
- Organisations are created as a sales channel
- Retail stores register within the organisation, internally they are handled as categories
- Retail stores can use an App to upload products
- Users (buyers) can browse retail stores and see what’s in stock
 
This is how the landing page for an organisation or local authority looks:

![Landing page for a local authority](readme_sc_lp_org.jpg?raw=true "Landing page for a local authority")

And here is an example of a retailer's landing page: 
 
![Landing page for a retailer](readme_sc_lp_retail.jpg?raw=true "Landing page for a retailer") 

## Technology

# How to install

## The Portal

Prerequisites: [docker](https://docs.docker.com/install/), [docker-compose](https://docs.docker.com/compose/install/), [node/npm](https://nodejs.org/en/download/)

> :warning: **The docker setup currently only works if your user ID is 1000.**   
> Execute `id -u` on your terminal to check.

Clone the project:

```shell script
git clone https://github.com/shopwareDowntown/downtown.git
```

Change into the project directory, then start the docker containers, add the cache directory and change into the app container:

```shell script
cd downtown
```

```shell script
docker-compose build
```

```shell script
docker-compose up -d
```

```shell script
docker-compose exec app_server sh
```

When inside the app container, do a basic install, generate a JWT secret and an App Secret, then exit the container:

```shell script
bin/console system:install --create-database --basic-setup --force
```

```shell script
bin/console system:generate-jwt-secret --force
```

```shell script
bin/console system:generate-app-secret # put into docker-compose.yml
```

```shell script
exit
```

The App Secret is only shown on screen, you have to put it manually into the `docker-compose.yml`, right after `- APP_SECRET=`.

Then regenerate the containers by re-executing up:

```shell script
docker-compose up -d
```

Please note:

- Administration is available at http://localhost:8000/admin with user `admin` and password `shopware`
- Each sales channel represents an organisation/local authority
- Merchants show up in categories after registration and activation
- Merchants register through the separate Angular Merchant Administration described below

You can shut down the portal with this command:

```shell script
docker-compose down --remove-orphans
```

## The Angular Merchant Administration

Currently there is no docker container available, so you need to start the project using npm.

Change into the directory `src/MerchantFrontend`. Then install dependencies and run the project: 

```shell script
cd src/MerchantFrontend
```

```shell script
npm install && npm run start
```

After the promt `Compiled successfully`, the merchant portal is available at [http://localhost:4200/](http://localhost:4200/).

Please be aware: The registration for organisations is currently not wired up to the portal, it's just a hubspot form,
for production use replace it with your own. For new organisations please create a sales channel manually in [the portal](http://localhost:8000/admin).

Merchants are able to register and choose a category. To activate a merchant either click on the link in the registration request e-mail or,
in case you haven't set up e-mail sending in the portal, do it directly in the database:

```shell script
docker-compose exec mysql mysql -p # password is root
```

```sql
USE downtown;
```

```sql
UPDATE merchant SET active=1 WHERE email='merchant@email.example';
```

```sql
quit;
```

# Contributing

You have an idea or you found an issue? Please open an issue here: [shopwareDowntown/portal/issues](https://github.com/shopwareDowntown/portal/issues)
Help retailers by contributing to this project. 

# Contributors

[![shyim](avatars/shyim.png?raw=true "shyim")](https://github.com/shyim) [![arnoldstoba](avatars/arnoldstoba.png?raw=true "arnoldstoba")](https://github.com/arnoldstoba) [![PaddyS](avatars/paddys.png?raw=true "PaddyS")](https://github.com/PaddyS) [![FloBWer](avatars/flobwer.png?raw=true "FloBWer")](https://github.com/FloBWer) [![JanPietrzyk](avatars/janpietrzyk.png?raw=true "JanPietrzyk")](https://github.com/JanPietrzyk) [![PascalThesing](avatars/pascalthesing.png?raw=true "PascalThesing")](https://github.com/PascalThesing) ![Kevin Mattutat](avatars/kevin-mattutat-spaceparrots-dekevin-mattutat.png?raw=true "Kevin Mattutat") ![Andreas Wolf](avatars/a-wolf-shopware-comandreas-wolf.png?raw=true "Andreas Wolf") [![and-wolf](avatars/and-wolf.png?raw=true "and-wolf")](https://github.com/and-wolf) [![oterhaar](avatars/oterhaar.png?raw=true "oterhaar")](https://github.com/oterhaar) [![MalteJanz](avatars/maltejanz.png?raw=true "MalteJanz")](https://github.com/MalteJanz) [![seggewiss](avatars/seggewiss.png?raw=true "seggewiss")](https://github.com/seggewiss) [![maike93](avatars/maike93.png?raw=true "maike93")](https://github.com/maike93) ![Maike Sestendrup](avatars/m-sestendrup-shopware-commaike-sestendrup.png?raw=true "Maike Sestendrup") [![marcelbrode](avatars/marcelbrode.png?raw=true "marcelbrode")](https://github.com/marcelbrode) [![swDennis](avatars/swdennis.png?raw=true "swDennis")](https://github.com/swDennis) ![Oliver Terhaar](avatars/o-terhaar-shopware-comoliver-terhaar.png?raw=true "Oliver Terhaar") [![xPand4B](avatars/xpand4b.png?raw=true "xPand4B")](https://github.com/xPand4B) ![Carlos Jansen](avatars/c-jansen-shopware-comcarlos-jansen.png?raw=true "Carlos Jansen") [![Carlosjan](avatars/carlosjan.png?raw=true "Carlosjan")](https://github.com/Carlosjan) [![Draykee](avatars/draykee.png?raw=true "Draykee")](https://github.com/Draykee) [![jakob-kruse](avatars/jakob-kruse.png?raw=true "jakob-kruse")](https://github.com/jakob-kruse) [![lukasrump](avatars/lukasrump.png?raw=true "lukasrump")](https://github.com/lukasrump) [![SebastianFranze](avatars/sebastianfranze.png?raw=true "SebastianFranze")](https://github.com/SebastianFranze) [![Christian-Rades](avatars/christian-rades.png?raw=true "Christian-Rades")](https://github.com/Christian-Rades) [![florianklockenkemper](avatars/florianklockenkemper.png?raw=true "florianklockenkemper")](https://github.com/florianklockenkemper) [![niklas-rudde](avatars/niklas-rudde.png?raw=true "niklas-rudde")](https://github.com/niklas-rudde) [![dnoegel](avatars/dnoegel.png?raw=true "dnoegel")](https://github.com/dnoegel) ![Jakob Kruse](avatars/j-kruse-shopware-comjakob-kruse.png?raw=true "Jakob Kruse") ![Luke Wenkers](avatars/l-wenkers-shopware-comluke-wenkers.png?raw=true "Luke Wenkers") 
