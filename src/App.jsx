import { useEffect, useState } from 'react';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link,
} from 'react-router-dom';
import Parse from 'parse';
import { Box } from '@material-ui/core';
import ContestList from './pages/contest-list';
import Contest from './pages/contest';
import Home from './pages/home';
import Privacy from './pages/privacy';

const headerStyle = {
  color: '#888',
  fontSize: '1.25rem',
  lineHeight: 2,
  marginLeft: '1rem',
  textTransform: 'uppercase',
  textDecoration: 'none',
};

const footerStyle = {
  textAlign: 'center',
  marginTop: '1rem',
};

function App() {
  const [loginStatus, setLoginStatus] = useState(false);

  useEffect(() => {
    Parse.User.currentAsync()
      .then((user) => {
        if (user) setLoginStatus(true);
      });
  }, []);

  return (
    <Router>
      <header>
        <Box><Link to="/" style={headerStyle}>Instant Q&amp;A Contest</Link></Box>
      </header>
      <Switch>
        <Route exact path="/">
          { loginStatus ? <ContestList /> : <Home /> }
        </Route>
        <Route exact path="/privacy-policy">
          <Privacy />
        </Route>
        { loginStatus && (
        <>
        <Route path="/:id">
          <Contest />
        </Route>
        </>
        )}
      </Switch>
      <footer style={footerStyle}>
        Code, Wiki and Discussion of IQAC is available at&nbsp;
        <a href="https://github.com/dj6082013/IQAC/">Github</a>
      </footer>
    </Router>
  );
}

export default App;
