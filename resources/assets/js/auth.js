class Auth {
    constructor () {
        this.token = window.localStorage.getItem('token');

        let userData = window.localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : null;


        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
            this.getUser();
        }
    }

    login (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        this.token = token;
        this.user = user;

        Event.$emit('userLoggedIn');
    }

    getUser() {
        api.call('get', '/api/user')
            .then(({data}) => {
                this.user = data;
                window.localStorage.setItem('user', JSON.stringify(data));
            });
    }

    logout() {
        axios.post('/api/logout').then(() => {
            this.token = null;
            this.user = null;
            window.localStorage.clear();
            Event.$emit('userLoggedIn');

        });
    }

    check () {
        return !! this.token;
    }

    locked () {
        if (this.user.locked === 1)
            return true;
        else return false;
    }
}

export default Auth;
