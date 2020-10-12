const fs = require('fs')

//read the user data from json file
const save = (db, data) => {
    const stringifyData = JSON.stringify(data)
    fs.writeFileSync('./data/'+db+'.json', stringifyData)
}

//get the user data from json file
const get = (db) => {
    const jsonData = fs.readFileSync('./data/'+db+'.json')
    return JSON.parse(jsonData)    
}

module.exports = {
  save,
  get
}