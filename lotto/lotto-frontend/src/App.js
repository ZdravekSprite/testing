import React, { useState, useEffect } from 'react'
import lotteryService from './services/lotteries'

const App = () => {
  const [lotteries, setLotteries] = useState([])

  useEffect(() => {
    lotteryService
      .getAll()
      .then(initialLotteries => {
        setLotteries(initialLotteries)
      })
  }, [])

  console.log(lotteries)

  return (
    <div>
      <h1>Lotto</h1>
      <h2>{lotteries.name}</h2>
      <h3>{lotteries.draws_info[0]}/{lotteries.draws_info[1]}:{lotteries.bonus_info[0]}/{lotteries.bonus_info[1]}</h3>
      <ul>
        {lotteries.draws.map((lottery, i) =>
          <li
            key={i}
            className='lottery'
          >
          {lottery.date} 
          </li>
        )}
      </ul>
    </div>
  )
}

export default App