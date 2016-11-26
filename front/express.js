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
        id: 1,
        created_at: '1970-01-01',
        title: 'Very first post',
        image: 'https://pp.vk.me/c836133/v836133667/fa72/qXmhC4F18NM.jpg',
        description: 'Новые треки супер группы<br><br> Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы',
        tags: ['pagan', 'folk', 'russia', 'ivancore'],
        tracks: [],
    }]);
});

function* makeTracks(times) {
    for (let i = 0; i < times; i += 1) {
        yield {
            id: 456240450,
            owner_id: 2000120414,
            artist: 'Super group',
            title: 'Track no ' + (i + 1),
            link: ''
        };
    }
}

app.get('/api/posts/:id', (req, res) => {
    res.json({
        id: 1,
        created_at: '1970-01-01',
        title: 'Very first post',
        image: 'https://pp.vk.me/c836133/v836133667/fa72/qXmhC4F18NM.jpg',
        description: 'Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы Новые треки супер группы',
        tags: ['pagan', 'folk', 'russia', 'ivancore'],
        tracks: [...makeTracks(5)],
    });
});

app.get('/api/tracks/:id', (req, res) => {
    res.json({link: 'https://psv4.vk.me/c4763/u5620198/audios/31d1027c14a3.mp3?aa=' +(+ new Date())})
});

app.get('/api/tags', (req, res) => {
    res.json(['pagan', 'folk', 'russia', 'ivancore']);
});

app.post('/api/posts-refresh', (req, res) => {
    res.sendStatus(204);
    res.json(null);
});

app.get('/*', (req, res) => {
    res.render('index.html');
});

app.listen(8080, () => {
    console.log('Example app listening on port 3000!');
});