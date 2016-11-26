import express from 'express';
import ejs from 'ejs';

const app = express();

app.set('views', __dirname + '/static');
app.engine('html', ejs.renderFile);
app.set('view engine', 'ejs');

app.use(express.static('static'));

app.post('/api/token', (req, res) => {
    res.json({token: 'SUPPASIKRETNETOKENMAZAFAKA'})
});

app.get('/api/posts', (req, res) => {
    res.json([]);
});

app.post('/api/posts-refresh', (req, res) => {
    res.sendStatus(204);
    res.json(null);
});

app.get('/*', (req, res) => {
    res.render('index.html');
});

app.listen(3000, () => {
    console.log('Example app listening on port 3000!');
});