import React from 'react'
import Draw from './Draw'
import Togglable from './Togglable'

const Lottery = ({ lottery }) => {

  const drawFormRef = React.createRef()

  return (
    <div className='lottery' >
      <h2>{lottery.name}</h2>
      <h3>{lottery.draws_info[0]}/{lottery.draws_info[1]}:{lottery.bonus_info[0]}/{lottery.bonus_info[1]}</h3>
      <Togglable buttonLabel='add new draw' ref={drawFormRef}>
        <p>Add draw</p>
      </Togglable>
      <ul>
        {lottery.draws.map((draw, i) =>
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