const info = (...params) => {
  console.log(...params)
}

const request = (req) => {
  console.log('Method:', req.method)
  console.log('Path:  ', req.path)
  console.log('Body:  ', req.body)
  console.log('---')
}

module.exports = {
  info,
  request
}