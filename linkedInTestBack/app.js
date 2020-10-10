const app = (req, res) => {
    res.writeHead(200, { 'Content-Type': 'text/plain' })
    res.end('Hello World')
  }
  
module.exports = app