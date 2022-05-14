import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';
import 'bootstrap/dist/css/bootstrap.css';
import Header from "./header/Header";
import reportWebVitals from './reportWebVitals';
import AlbumsPage from "./album/AlbumsPage";

const root = ReactDOM.createRoot(
  document.getElementById('root') as HTMLElement
);
root.render(
  <React.StrictMode>
    <Header loggedIn={false} isMobile={false}/>
    <App />
    <AlbumsPage artistID="6fOMl44jA4Sp5b9PpYCkzz"/>
    {/* <Album name="Kingdom of Silence" releaseDate="2020-01-01" url="" img="https://i.scdn.co/image/ab67616d0000b273cf48e14a726ff6c9977fa641" type="album"/> */}
  </React.StrictMode>
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
