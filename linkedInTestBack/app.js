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
    return res.status(409).send({ error: true, msg: 'test not exist' })
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
    return res.status(409).send({ error: true, msg: 'test not exist' })
  }
  const db = dbData.get(test)
  //filter the data
  //const qData = db.filter( data => data.q.toString().replace("?", "") == q )
  const qData = db.filter(data => data.q.toString().includes(q))
  res.send(qData)
})

/* Create - POST method */
app.post('/api/add', (req, res) => {
  //get the new req data from post request
  const reqData = req.body
  //check if the slug fields are missing
  if (reqData.slug == null) {
    return res.status(401).send({ error: true, msg: 'slug data missing' })
  }

  //check if the slug exist already
  if (!testExist(reqData.slug)) {
    if (reqData.content == null) {
      return res.status(401).send({ error: true, msg: 'content for new slug data missing' })
    }
    //get the existing tests data
    const existTests = dbData.get('tests')

    //append the req data
    const newTest = {
      content: reqData.content,
      slug: reqData.slug
    }
    existTests.push(newTest)
    //save the new req data
    dbData.save('tests', existTests)
    dbData.create(reqData.slug)
    res.send({ success: true, msg: 'Test added to list successfully' })
  }
  if (reqData.q == null) {
    return res.status(401).send({ error: true, msg: 'slug exist but q missing' })
  }

  //get the existing test data
  const existQs = dbData.get(reqData.slug)

  if (existQs.find(existQs => existQs.q[0] === reqData.q)) {
    return res.status(409).send({error: true, msg: 'q already exist'})
  }
  existQs.push(newQ(reqData))
  //save the new req data
  dbData.save(reqData.slug, existQs)
  res.send({ success: true, msg: 'Q added to test successfully' })

  //res.send({ success: true, msg: 'add end' })
})

/* Create - POST method */
app.post('/api/quiz', (req, res) => {
  //return res.status(401).send(req.body)
  const reqData = req.body
  if (reqData.title == null || reqData.q == null || reqData.a == null) {
    return res.status(401).send({ error: true, msg: 'something missing'})
  }

  const db = dbData.get('tests')
  const test = db.filter(t => t.content.toString().includes(reqData.title.replace(" Assessment", "")))[0].slug
  const testDb = dbData.get(test)
  const testQ = testDb.filter(q => q.q.toString().includes(reqData.q))
  if (testQ.length > 0) {
    if (testQ[0].q[1] > 0) {
      res.send({a: testQ[0].a[testQ[0].q[1]-1][0]})
    }
    res.send({q: testQ[0].q[0]})
  }
  testDb.push(newQ(reqData))
  dbData.save(test, testDb)
  res.send({q: reqData.q})
})

/* util functions */
const newQ = (data) => {
  const temp = {
    q: [data.q, 0],
    a: []
  }
  for (var i = 0; i < data.a.length; i++) {
    temp.a[i] = [data.a[i], 0]
  }
  return temp
}
const testExist = (test) => {
  //get the list of existing tests
  const existTest = dbData.get('tests')
  //check if the test exist or not       
  return existTest.find(dbTest => dbTest.slug === test)
}
/* util functions ends */

app.use(unknownEndpoint)

module.exports = app