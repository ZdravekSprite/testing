import React from 'react'

const Filter = ({ value, onChange }) => {

  return (
    <input
      value={value}
      onChange={onChange}
    />
  )
}

export default Filter