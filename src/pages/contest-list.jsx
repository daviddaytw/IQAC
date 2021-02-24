import Parse from 'parse';
import { useEffect, useState } from 'react';
import {
  Button,
  Container,
  Dialog,
  DialogActions,
  DialogTitle,
  DialogContent,
  TableContainer,
  Table,
  TableHead,
  TableBody,
  TableRow,
  TableCell,
  Typography,
  Toolbar,
  Tooltip,
  TextField,
  IconButton,
  Paper,
} from '@material-ui/core';
import { Link, useHistory } from 'react-router-dom';
import AddIcon from '@material-ui/icons/Add';

export default function ContestList() {
  const history = useHistory();
  const [contests, setContests] = useState([]);
  const Contest = Parse.Object.extend('Contest');
  useEffect(() => {
    const query = new Parse.Query(Contest);
    query.find().then((results) => {
      setContests(results);
    }).catch((err) => {
      throw err;
    });
  }, []);

  const [showCreateDialog, setShowCreateDialog] = useState(false);
  const handleCreateOpen = () => { setShowCreateDialog(true); };
  const handleCreateClose = () => { setShowCreateDialog(false); };
  const handleCreateAction = () => {
    const name = document.getElementById('name').value;
    const contest = new Contest();
    contest.set('name', name);
    const contestACL = new Parse.ACL(Parse.User.current());
    contestACL.setPublicReadAccess(true);
    contest.setACL(contestACL);
    contest.save().then((result) => {
      history.push(`/${result.id}`);
    }, (error) => {
      throw error.message;
    });
  };
  const handleLogout = () => {
    Parse.User.logOut().then(() => {
      history.go(0);
    });
  };

  return (
    <Container>
      <Toolbar>
        <Typography variant="h5" id="tableTitle" component="div">
          Contest List
        </Typography>

        <Tooltip title="Create contest">
          <IconButton onClick={handleCreateOpen} aria-label="create contest">
            <AddIcon />
            <Dialog open={showCreateDialog} onClose={handleCreateClose} aria-labelledby="form-dialog-title">
              <DialogTitle id="form-dialog-title">Create Contest</DialogTitle>
              <DialogContent>
                <TextField
                  autoFocus
                  margin="dense"
                  id="name"
                  label="Contest Name"
                  type="text"
                  fullWidth
                />
              </DialogContent>
              <DialogActions>
                <Button onClick={handleCreateClose} color="primary">
                  Cancel
                </Button>
                <Button onClick={handleCreateAction} color="primary">
                  Create
                </Button>
              </DialogActions>
            </Dialog>
          </IconButton>
        </Tooltip>
        <Button onClick={handleLogout}>Logout</Button>
      </Toolbar>
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Name</TableCell>
              <TableCell align="right">Begin</TableCell>
              <TableCell align="right">Finish</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {contests.map((contest) => (
              <TableRow key={contest.id}>
                <TableCell component="th" scope="row">
                  <Link style={{ textDecoration: 'none', color: 'rgba(0, 0, 0, 0.87)' }} to={`/${contest.id}`}>
                    <Typography variant="h6">{contest.get('name')}</Typography>
                  </Link>
                </TableCell>
                <TableCell align="right">{contest.get('beginAt') && contest.get('beginAt').toLocaleString()}</TableCell>
                <TableCell align="right">{contest.get('endAt') && contest.get('endAt').toLocaleString()}</TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
    </Container>
  );
}
