import logo from './logo.svg';
import Button from "./button/Button";
import './App.css';

function App() {
    const handleMoreClick = () => {
    }
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <p>
          Edit <code>src/App.tsx</code> and save to reload.
        </p>
        <a
          className="App-link"
          href="https://reactjs.org"
          target="_blank"
          rel="noopener noreferrer"
        >
          Learn React
        </a>
        <Button name="test" value="test" onClick={handleMoreClick} />
      </header>
    </div>
  );
}

export default App;
