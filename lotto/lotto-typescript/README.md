## lotto

- > .gitignore

```
node_modules
package-lock.json
```

```
npm init -yes
npm install --save-dev typescript
npm run tsc -- --init
npm install express
npm install --save-dev eslint @types/express @typescript-eslint/eslint-plugin @typescript-eslint/parser
npm install --save-dev ts-node-dev
```

- > package.json

```
{
  // ...
  "scripts": {
    "tsc": "tsc",
    "dev": "ts-node-dev index.ts",
    "lint": "eslint --ext .ts ."
  },
  // ...
}
```