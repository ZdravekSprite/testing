import axios from 'axios'
const baseUrl = 'http://localhost:3001/euro_jackpot'

const getAll = () => {
  const request = axios.get(baseUrl)
  return request.then(response => response.data)
}
/*
const create = (draw) => {
  const request = axios.post(baseUrl, draw)
  return request.then(response => response.data)
}
*/
// update
const create = (draw) => {
  const request = axios.put(baseUrl, draw)
  return request.then(response => response.data)
}

export default { getAll, create }