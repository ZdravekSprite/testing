import React, { useState } from 'react'

const NewDraw = ({ createDraw }) => {
  const [date, setDate] = useState('')
  const [draw, setDraw] = useState('')
  const [bonus, setBonus] = useState('')
  const [oneLine, setOneLine] = useState('')

  const handleNewDraw = (event) => {
    event.preventDefault()

    createDraw({
      date,
      draw: draw.split(',').map(e => Number(e)),
      bonus: bonus.split(',').map(e => Number(e))
    })

    setDate('')
    setDraw('')
    setBonus('')
  }

  const splitOneLine = str => {
    const arr = str.split(';')
    return {
      date: arr[0],
      draw: arr[1].split(',').map(e => Number(e)),
      bonus: arr[2].split(',').map(e => Number(e))
    }
  }

  const handleNewDrawOne = (event) => {
    event.preventDefault()
    createDraw(splitOneLine(oneLine))
    setOneLine('')
  }

  return (
    <div>
      <h2>create new</h2>
      <form onSubmit={handleNewDraw}>
        <div>
          date
          <input
            id='date'
            value={date}
            onChange={({ target }) => setDate(target.value)}
          />
        </div>
        <div>
          draw
          <input
            id='draw'
            value={draw}
            onChange={({ target }) => setDraw(target.value)}
          />
        </div>
        <div>
          bonus
          <input
            id='bonus'
            value={bonus}
            onChange={({ target }) => setBonus(target.value)}
          />
        </div>
        <button id="create">create</button>
      </form>
      <br />
      <form onSubmit={handleNewDrawOne}>
        <div>
          oneLine
          <input
            id='oneLine'
            value={oneLine}
            onChange={({ target }) => setOneLine(target.value)}
          />
        </div>
        <button id="createLine">create</button>
      </form>
    </div>
  )
}

export default NewDraw