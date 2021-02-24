import {
  Button,
  Divider,
  Grid,
  TableContainer,
  Table,
  TableHead,
  TableBody,
  TableRow,
  TableCell,
  TextField,
  Paper,
} from '@material-ui/core';
import PropTypes from 'prop-types';
import Parse from 'parse';
import { useEffect, useState } from 'react';

export default function Submissions({ contest }) {
  const [selected, setSelected] = useState(0);
  const [submissions, setSubmissions] = useState([]);

  useEffect(() => {
    const Submission = Parse.Object.extend('Submission');
    const query = new Parse.Query(Submission);
    query.equalTo('contest', contest);
    query.include('question');
    query.descending('createdAt');
    query.find().then((result) => {
      setSubmissions(result);
    });
  }, []);

  const current = submissions[selected];

  const handleSubmit = () => {
    current.set('score', parseInt(document.getElementById('score').value, 10));
    current.set('comment', document.getElementById('comment').value);
    current.save();
    setSelected(0);
  };

  return (
    <Grid container spacing={3}>
      <Grid item xs>
        <TableContainer component={Paper}>
          <Table>
            <TableHead>
              <TableRow>
                <TableCell>ID</TableCell>
                <TableCell align="right">Question</TableCell>
                <TableCell align="right">Score</TableCell>
                <TableCell align="right">Comment</TableCell>
                <TableCell align="right">Submit Time</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {
                submissions.map((submission, idx) => (
                  <TableRow key={submission.id}>
                    <TableCell component="th" scope="row">{submission.id}</TableCell>
                    <TableCell align="right">{submission.get('question').get('title')}</TableCell>
                    <TableCell align="right">
                      <Button
                        variant="contained"
                        onClick={() => { setSelected(idx); }}
                        color={submission.get('score') === undefined ? 'secondary' : 'default'}
                      >
                        {submission.get('score') === undefined ? 'Pending' : submission.get('score')}
                      </Button>
                    </TableCell>
                    <TableCell align="right">{submission.get('comment')}</TableCell>
                    <TableCell align="right">{submission.get('createdAt').toLocaleString()}</TableCell>
                  </TableRow>
                ))
              }
            </TableBody>
          </Table>
        </TableContainer>
      </Grid>
      { current && current.getACL().getWriteAccess(Parse.User.current()) && (
        <Grid>
          <Paper style={{ padding: '1rem' }}>
            <h1>{current.get('question').get('title')}</h1>
            {current.get('question').get('content')}
            <Divider />
            <p>{current.get('answer')}</p>
            <TextField id="score" label="Score" type="number" defaultValue={current.get('score')} />
            <TextField id="comment" label="Comment" defaultValue={current.get('comment')} />
            <Button variant="contained" color="primary" onClick={handleSubmit}>Submit</Button>
          </Paper>
        </Grid>
      )}
    </Grid>
  );
}

Submissions.propTypes = {
  contest: PropTypes.shape({
    id: PropTypes.string,
    get: PropTypes.func,
  }),
};

Submissions.defaultProps = {
  contest: {},
};
