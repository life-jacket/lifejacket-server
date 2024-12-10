import ReactDOM from 'react-dom/client'

import App from './App';

import './index.css';

    
if (document.getElementById('lifejacket-server-settings')) {
    const root = ReactDOM.createRoot(document.getElementById('lifejacket-server-settings'))
    root.render( <App/> );
}