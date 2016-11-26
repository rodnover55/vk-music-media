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
    res.json([{
        id: 0,
        created_at: '1970-01-01',
        title: 'Very first post',
        image: 'https://pp.vk.me/c836133/v836133667/fa72/qXmhC4F18NM.jpg',
        description: 'Новые треки супер группы',
        tags: ['pagan', 'folk', 'russia', 'ivancore'],
        tracks: [],
    }]);
});

app.get('/api/posts/:id', (req, res) => {
    res.json({
        id: 0,
        created_at: '1970-01-01',
        title: 'Very first post',
        image: 'https://pp.vk.me/c836133/v836133667/fa72/qXmhC4F18NM.jpg',
        description: 'Новые треки супер группы',
        tags: ['pagan', 'folk', 'russia', 'ivancore'],
        tracks: [
            'http://qwe.ru/1.mp3',
            'http://qwe.ru/2.mp3',
            'http://qwe.ru/3.mp3',
            'http://qwe.ru/4.mp3',
            'http://qwe.ru/5.mp3',
        ],
    });
});

app.get('/api/tags', (req, res) => {
    res.json(['pagan', 'folk', 'russia', 'ivancore']);
});

app.post('/api/posts-refresh', (req, res) => {
    res.sendStatus(500);
    res.json(null);
});

app.get('/*', (req, res) => {
    res.render('index.html');
});

app.listen(3000, () => {
    console.log('Example app listening on port 3000!');
});