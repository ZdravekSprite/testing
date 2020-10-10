const http = require('http')
const config = require('./utils/config') 

const app = http.createServer((req, res) => {
  res.writeHead(200, { 'Content-Type': 'text/plain' })
  res.end('Hello World')
})

app.listen(config.PORT)
console.log(`Server running on port ${config.PORT}`)