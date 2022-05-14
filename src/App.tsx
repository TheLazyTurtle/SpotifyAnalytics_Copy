import './App.css';
import Header from './header/Header';
import 'bootstrap/dist/css/bootstrap.css';

function App() {
    return (
        <div className="App">
            <Header loggedIn={false}/>
        </div>
    );
}

export default App;
