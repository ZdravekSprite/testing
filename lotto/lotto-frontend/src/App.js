import React, { useState, useEffect } from 'react'
import lotteryService from './services/lotteries'
import Lottery from './components/Lottery'

const App = () => {
  const [lotteries, setLotteries] = useState(null)

  useEffect(() => {
    lotteryService
      .getAll()
      .then(lotteries => {
        setLotteries(lotteries)
      })
  }, [])

  if (!lotteries)
    return null

  console.log(lotteries)

  return (
    <div>
      <h1>Lotto</h1>
      <Lottery lottery={lotteries} />
    </div>
  )
}

export default App