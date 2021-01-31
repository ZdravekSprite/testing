const TableRow = ({notShow, opis, sati, iznos, bold }) => {
  const toKn = (kn) => isNaN(kn)||kn==='' ? kn : (kn*1).toFixed(2)
  return (
    <div className={'row' + (bold ? ' bold' : '') + (notShow ? ' hidden' : '')}>
      <div className="col-10 col-s-10">
        {opis}
      </div>
      <div className="col-1 col-s-1 center">
        {toKn(sati)}
      </div>
      <div className="col-1 col-s-1 right">
        {toKn(iznos)}
      </div>
    </div>
  )
}

TableRow.defaultProps = {
  opis: '',
  sati: '',
  iznos: '',
  bold: false
}

export default TableRow
