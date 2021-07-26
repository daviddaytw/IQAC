import { useEffect, useState } from 'react';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link,
} from 'react-router-dom';
import Parse from 'parse';
import { makeStyles } from '@material-ui/core/styles';
import {
  AppBar, Avatar, Button, Toolbar, Typography,
} from '@material-ui/core';
import Account from './pages/account';
import ContestList from './pages/contest-list';
import Contest from './pages/contest';
import Home from './pages/home';
import Privacy from './pages/privacy';

const useStyles = makeStyles((theme) => ({
  root: {
    flexGrow: 1,
  },
  menuButton: {
    marginRight: theme.spacing(2),
  },
  link: {
    color: 'inherit',
    textDecoration: 'inherit',
    cursor: 'inherit',
  },
  brand: {
    color: 'inherit',
    textDecoration: 'inherit',
    cursor: 'inherit',
    flexGrow: 1,
  },
  footer: {
    textAlign: 'center',
    marginTop: '1rem',
  },
}));

function App() {
  const classes = useStyles();
  const [userInfo, setUserInfo] = useState();

  useEffect(() => {
    Parse.User.currentAsync()
      .then((user) => {
        if (user) {
          setUserInfo({
            name: user.get('username'),
            avatarUrl: user.get('avatarUrl'),
          });
        }
      });
  }, []);

  return (
    <Router>
      <AppBar position="static">
        <Toolbar>
          <Link className={classes.brand} to="/">
            <Typography variant="h6">Instant Q&amp;A Contest</Typography>
          </Link>
          { userInfo && (
            <Link className={classes.link} to="/account">
              <Button color="inherit">
                {userInfo.name}
                <Avatar alt={userInfo.name} src={userInfo.avatarUrl} />
              </Button>
            </Link>
          )}
        </Toolbar>
      </AppBar>
      <Switch>
        <Route exact path="/">
          { userInfo ? <ContestList /> : <Home /> }
        </Route>
        <Route exact path="/privacy-policy">
          <Privacy />
        </Route>
        { userInfo && (
          <Route exact path="/account">
            <Account />
          </Route>
        )}
        <Route path="/:id">
          <Contest />
        </Route>
      </Switch>
      <footer className={classes.footer}>
        Code, Wiki and Discussion of IQAC is available at&nbsp;
        <a href="https://github.com/dj6082013/IQAC/">Github</a>
      </footer>
    </Router>
  );
}

export default App;
