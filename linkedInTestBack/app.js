const express = require('express')
const app = express()

let tests = [
  {
    content: "OOP",
    qa: [
      {
        q: ["What is an example of dynamic binding?", 0],
        a1: ["method overriding", 0],
        a2: ["any method", 0],
        a3: ["method overloading", 0],
        a4: ["compiling", 0]
      },
      {
        q: ["For which case would the use of a static attribute be appropriate?", 0],
        a1: ["the weather conditions for each house in a small neighborhood", 0],
        a2: ["the number of people in each house in a small neighborhood", 0],
        a3: ["the lot size for each house in a small neighborhood", 0],
        a4: ["the color of each house in a small neighborhood", 0]
      }
    ]
  },
  {
    content: "Java",
    qa: [
      {
        q: ["Given the string \"strawberries\" saved in a variable called fruit, what would \"fruit.substring(2, 5)\" return?", 0],
        a1: ["raw", 0],
        a2: ["rawb", 0],
        a3: ["awb", 0],
        a4: ["traw", 0]
      },
      {
        q: ["How can you achieve runtime polymorphism in Java?", 0],
        a1: ["method overriding", 0],
        a2: ["method overloading", 0],
        a3: ["method overrunning", 0],
        a4: ["method calling", 0]
      }
    ]
  }
]

/*
const app = (req, res) => {
  res.writeHead(200, { 'Content-Type': 'text/plain' })
  res.end('Hello World')
  res.writeHead(200, { 'Content-Type': 'application/json' })
  res.end(JSON.stringify(tests))
}
*/

app.get('/', (req, res) => {
  let root = '<h1>Hello World!</h1>'
  root += '<p><a href="/api/tests">Tests</a></p>'
  res.send(root)
})

app.get('/api/tests', (req, res) => {
  res.json(tests)
})

module.exports = app