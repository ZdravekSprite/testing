import React, { useState } from 'react'
import Draw from './Draw'
import NewDraw from './NewDraw'
import Togglable from './Togglable'
import lotteryService from '../services/lotteries'

const Lottery = ({ lottery }) => {
  const [draws, setDraws] = useState(lottery.draws)

  const drawFormRef = React.createRef()

  const createDraw = async (draw) => {
    //console.log(draw)
    try {
      const newDraws = draws.concat({
        id: draws.length + 1,
        ...draw
      })
      setDraws(newDraws)
      lottery.draws = newDraws
      lotteryService.create(lottery)
    } catch (exception) {
      console.log(exception)
    }
  }

  return (
    <div className='lottery' >
      <h2>{lottery.name}</h2>
      <h3>{lottery.draws_info[0]}/{lottery.draws_info[1]}:{lottery.bonus_info[0]}/{lottery.bonus_info[1]}</h3>
      <Togglable buttonLabel='add new draw' ref={drawFormRef}>
        <NewDraw createDraw={createDraw} />
      </Togglable>
      <ul>
        {draws.map((draw, i) =>
          <Draw
            key={i}
            draw={draw}
          />
        )}
      </ul>
    </div>
  )
}

export default Lottery