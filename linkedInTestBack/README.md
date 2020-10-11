# LinkedIn Test Backend app

```
npm init --yes
npm install --save-dev nodemon
npm install dotenv
npm install json-server --save-dev
npm install express
```

> .gitignore

```
node_modules
.env
```

> package.json

```
"start": "node index.js",
"dev": "nodemon index.js",
"server": "json-server -p3001 --watch db.json",
```