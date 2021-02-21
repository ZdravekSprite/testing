require('dotenv').config()

const PORT = process.env.PORT
const DB = process.env.DB

module.exports = {
  PORT,
  DB
}