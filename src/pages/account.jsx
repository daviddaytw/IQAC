import { makeStyles } from '@material-ui/core/styles';
import {
  CardMedia,
  Container,
  Grid,
  Table,
  TableBody,
  TableCell,
  TableRow,
  Typography,
} from '@material-ui/core';
import Parse from 'parse';

const useStyles = makeStyles(() => ({
  box: {
    padding: '0.5rem',
  },
}));

export default function Account() {
  const classes = useStyles();
  const user = Parse.User.current();

  return (
    <Container maxWidth="md">
      <Typography variant="h2">Account</Typography>
      <Grid container>
        <Grid item xs={6} className={classes.box}>
          <CardMedia component="img" src={user.get('avatarUrl')} />
        </Grid>
        <Grid item xs={6} className={classes.box}>
          <Table>
            <TableBody>
              <TableRow>
                <TableCell component="th" scope="row">Username</TableCell>
                <TableCell align="right">{user.get('username')}</TableCell>
              </TableRow>
              <TableRow>
                <TableCell component="th" scope="row">Email</TableCell>
                <TableCell align="right">{user.get('email')}</TableCell>
              </TableRow>
            </TableBody>
          </Table>
          <Typography color="error">Please email dj6082013@gmail.com for account deletion</Typography>
        </Grid>
      </Grid>
    </Container>
  );
}
