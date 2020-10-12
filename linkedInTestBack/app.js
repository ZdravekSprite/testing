const express = require('express')
const logger = require('./utils/logger')
const dbData = require('./utils/dbData')
const app = express()

//this line is required to parse the request body
app.use(express.json())

const requestLogger = (req, res, next) => {
  logger.request(req)
  next()
}

const unknownEndpoint = (req, res) => {
  res.status(404).send({ error: 'unknown endpoint' })
}

app.use(requestLogger)

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
  root = dbData
    .get('tests')
    .reduce((list, t) => list + `<p><a href="/api/${t.slug}">${t.content}</a></p>`, root)
  res.send(root)
})

/* Read - GET method */
app.get('/api/tests', (req, res) => {
  res.send(dbData.get('tests'))
})
app.get('/api', (req, res) => {
  res.send(dbData.get('tests'))
})

/* Read test - GET method */
app.get('/api/:test', (req, res) => {
  const test = req.params.test

  if (!testExist(test)) {
      return res.status(409).send({error: true, msg: 'test not exist'})
  }
  const db = dbData.get(test)
  res.send(db)
})


/* Read test q - GET method */
app.get('/api/:test/:q', (req, res) => {
  const test = req.params.test
  const q = req.params.q

  //return res.status(409).send({error: true, msg: `test: ${test} q: ${q}`})

  if (!testExist(test)) {
    return res.status(409).send({error: true, msg: 'test not exist'})
  }
  const db = dbData.get(test)
  //filter the userdata to remove it
  //const qdata = db.filter( data => data.q.toString().replace("?", "") == q )
  const qdata = db.filter( data => data.q.toString().includes(q))
  res.send(qdata)
})

/* util functions */
const testExist = (test) => {
  //get the list of existing tests
  const existTest = dbData.get('tests')
  //check if the test exist or not       
  return existTest.find( dbTest => dbTest.slug === test )
}
/* util functions ends */

app.use(unknownEndpoint)

module.exports = app