import { useState } from 'react'
import HomePage from './presentation/pages/general/inicioDashboard/HomePage'
/*import './App.css'*/

function App() {
  const [count, setCount] = useState(0)

  return (
      <HomePage />
  )
}

export default App
