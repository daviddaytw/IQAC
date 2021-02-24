/* eslint-disable no-alert */
/* eslint-disable no-shadow */
/* eslint-disable no-undef */
import { Button, Container } from '@material-ui/core';
import Parse from 'parse';
import { useHistory } from 'react-router-dom';
import { ReactComponent as Logo } from '../logo.svg';

export default function Home() {
  window.fbAsyncInit = () => {
    Parse.FacebookUtils.init({ // this line replaces FB.init({
      appId: process.env.REACT_APP_FACEBBOK_ID, // Facebook App ID
      cookie: true, // enable cookies to allow Parse to access the session
      xfbml: true, // initialize Facebook social plugins on the page
      version: 'v2.3', // point to the latest Facebook Graph API version, found in FB's App dashboard.
    });
  };

  // Include the Facebook SDK
  const includeSDK = (d, s, id) => {
    const fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    const js = d.createElement(s); js.id = id;
    js.src = '//connect.facebook.net/en_US/sdk.js';
    fjs.parentNode.insertBefore(js, fjs);
  };
  includeSDK(document, 'script', 'facebook-jssdk');

  const history = useHistory();
  const logIn = async () => {
    try {
      const user = await Parse.FacebookUtils.logIn('public_profile,email');
      if (!user.existed()) {
        FB.api('/me?fields=id,name,email,permissions', (response) => {
          user.set('username', response.name);
          user.set('email', response.email);

          user.save().then(() => {
            history.go(0);
          });
        });
      } else {
        history.go(0);
      }
    } catch (error) {
      alert('User cancelled the Facebook login or did not fully authorize.');
    }
  };

  return (
    <Container>
      <Logo style={{ display: 'block', margin: 'auto' }} />
      <Button style={{ display: 'block', margin: 'auto' }} color="primary" onClick={logIn}>Log In By Facebook</Button>
    </Container>
  );
}
