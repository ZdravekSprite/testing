import React from 'react'

const Draw = ({ draw }) => {

  return (
    <li className='draw' >
      {draw.date} / {draw.draw.toString()} / {draw.bonus.toString()}
    </li>
  )
}

export default Draw