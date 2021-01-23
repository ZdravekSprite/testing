function App() {
  const year = 2021
  const month = 1
  function getDaysInMonth (m, y) {
    // Here January is 1 based
    //Day 0 is the last day in the previous month
    return new Date(y, m, 0).getDate();
    // Here January is 0 based
    // return new Date(y, m+1, 0).getDate();
  }
  const daysInMonth = getDaysInMonth(month, year)
  // sunday x = 8
  // sateray x = 7
  function specDaysInMonth( m, y, x) {
    var days = [ x - (new Date( m +'/01/'+ y ).getDay()) ];
    for ( var i = days[0] + 7; i <= daysInMonth; i += 7 ) {
      days.push( i );
    }
    return days;
  }
  const sundays = specDaysInMonth(month, year, 8).length
  const saturdays = specDaysInMonth(month, year, 7).length
  const hoursNorm = (daysInMonth - sundays - saturdays) * 7 + saturdays * 5
  return (
    <div className="App">
      <p>year: {year}</p>
      <p>month: {month}</p>
      <p>days: {daysInMonth}</p>
      <p>sundays: {sundays}</p>
      <p>saturdays: {saturdays}</p>
      <p>hours: {hoursNorm}</p>
    </div>
  );
}

export default App;
