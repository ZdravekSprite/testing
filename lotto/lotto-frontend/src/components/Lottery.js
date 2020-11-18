import React, { useState } from 'react'
import Draw from './Draw'
import NewDraw from './NewDraw'
import Togglable from './Togglable'
import Filter from './Filter'
import lotteryService from '../services/lotteries'

const Lottery = ({ lottery }) => {
  const [draws, setDraws] = useState(lottery.draws)
  const [filterString, setStringFilter] = useState('')

  const drawFormRef = React.createRef()

  const handleFilterStringChange = (event) => {
    setStringFilter(event.target.value)
  }

  const createDraw = async (draw) => {
    console.log(draw)
    const newDraws = draws.concat({
      id: draws.length + 1,
      ...draw
    })
    setDraws(newDraws)
    lottery.draws = newDraws
    try {
      await lotteryService.create(lottery)
    } catch (exception) {
      console.log(exception)
    }
  }

  const drawsToShow = filterString.length === 0 ?
    draws :
    draws.filter(d => filterString.split(',').map(e => Number(e)).filter(e => e !== 0).every(r => d.draw.includes(r)))

  return (
    <div className='lottery' >
      <h2>{lottery.name}</h2>
      <h3>{lottery.draws_info[0]}/{lottery.draws_info[1]}:{lottery.bonus_info[0]}/{lottery.bonus_info[1]}</h3>
      <Togglable buttonLabel='add new draw' ref={drawFormRef}>
        <NewDraw createDraw={createDraw} />
      </Togglable>
      <Filter
        value={filterString}
        onChange={handleFilterStringChange}
      />
      <ul>
        {drawsToShow.map((draw, i) =>
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