import { useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import {
  Button, Divider, Grid, Paper, ListItem, ListItemText, TextField,
} from '@material-ui/core';
import Parse from 'parse';

export default function Questions({ contest }) {
  const [questions, setQuestions] = useState([]);
  const [selected, setSelected] = useState();

  useEffect(() => {
    const Question = Parse.Object.extend('Question');
    const query = new Parse.Query(Question);
    query.equalTo('contest', contest);
    query.find().then((result) => {
      setQuestions(result);
    });
  }, []);

  const handleSubmit = () => {
    const input = document.getElementById('answer');
    const Submission = Parse.Object.extend('Submission');
    const submission = new Submission();
    submission.set('answer', input.value);
    submission.set('question', questions[selected]);
    submission.set('contest', contest);
    submission.set('submitter', Parse.User.current());
    const acl = new Parse.ACL(questions[selected].get('judge'));
    acl.setReadAccess(Parse.User.current(), true);
    submission.setACL(acl);
    submission.save().then(() => {
      input.value = '';
    });
  };

  const handleCreate = () => {
    const Question = Parse.Object.extend('Question');
    const question = new Question();
    question.set('contest', contest);
    question.set('judge', Parse.User.current());
    const questionACL = new Parse.ACL(Parse.User.current());
    questionACL.setPublicReadAccess(true);
    question.setACL(questionACL);
    question.save().then((result) => {
      questions.push(result);
      setQuestions(questions);
      setSelected(questions.length - 1);
    });
  };

  const handleUpdate = () => {
    questions[selected].set('title', document.getElementById('title').value);
    questions[selected].set('content', document.getElementById('content').value);
    questions[selected].save();
  };

  const current = questions[selected];
  const editable = current && current.getACL().getWriteAccess(Parse.User.current());
  const isJudge = contest.getACL() != null;

  return (
    <Grid container>
      <Grid item xs={3}>
        {
          questions.map((question, idx) => (
            <ListItem
              button
              key={question.id}
              component="a"
              selected={question === current}
              onClick={() => {
                setSelected(idx);
                if (!editable) return;
                document.getElementById('title').value = questions[idx].get('title');
                document.getElementById('content').value = questions[idx].get('content');
              }}
            >
              <ListItemText primary={question.get('title')} />
            </ListItem>
          ))
        }
        <Divider />
        { isJudge && (
          <ListItem
            button
            component="a"
            onClick={handleCreate}
          >
            <ListItemText primary="Add Question" />
          </ListItem>
        )}
      </Grid>
      <Grid item xs={9}>
        { current
          && (editable
            ? (
              <Paper style={{ padding: '1rem' }}>
                <TextField id="title" label="Title" defaultValue={current.get('title')} />
                <TextField
                  id="content"
                  label="Statement"
                  defaultValue={current.get('content')}
                  multiline
                  fullWidth
                  rows={10}
                />
                <Button style={{ marginTop: '1rem' }} variant="contained" color="primary" onClick={handleUpdate}>Update</Button>
              </Paper>
            ) : (
              <Paper style={{ padding: '1rem' }}>
                <h1>{current.get('title')}</h1>
                {current.get('content')}
                <TextField
                  id="answer"
                  label="Your Answer"
                  multiline
                  fullWidth
                  rows={4}
                />
                <Button style={{ marginTop: '1rem' }} variant="contained" color="primary" onClick={handleSubmit}>Submit</Button>
              </Paper>
            )
          )}
      </Grid>
    </Grid>
  );
}

Questions.propTypes = {
  contest: PropTypes.shape({
    id: PropTypes.string,
    get: PropTypes.func,
    getACL: PropTypes.func,
    relation: PropTypes.func,
    save: PropTypes.func,
  }),
};

Questions.defaultProps = {
  contest: {},
};
