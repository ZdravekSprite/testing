import React from 'react'

const Lottery = ({ lottery }) => {

  return (
    <li className='lottery' >
      {lottery.date} 
    </li>
  )
}

export default Lottery