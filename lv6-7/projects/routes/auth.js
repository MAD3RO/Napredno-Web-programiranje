var express = require('express'),
    router = express.Router(),
    mongoose = require('mongoose'), //mongo connection
    bodyParser = require('body-parser'), //parses information from POST
    methodOverride = require('method-override'); //used to manipulate POST

//Any requests to this controller must pass through this 'use' function
//Copy and pasted from method-override
router.use(bodyParser.urlencoded({ extended: true }))
router.use(methodOverride(function(req, res){
    if (req.body && typeof req.body === 'object' && '_method' in req.body) {
        // look in urlencoded POST bodies and delete it
        var method = req.body._method
        delete req.body._method
        return method
    }
}))

//prikaz login forme
router.route('/login')
    .get( function(req, res, next) {
        res.render('auth/login');
    })
    //POST provjera korisnika prilikom prijave
    .post(function(req, res) {
// dohvaćanje vrijednosti iz forme
            var uname = req.body.username;
            var pasw = req.body.password;
            //traženje unesenog korisnika
        mongoose.model('User').findOne({username:uname, password:pasw}).exec( function (err, user) {
            if (err) {
                return callback(err)

            } else if (user) {
                //ukoliko pronađe postavlja session varijablu logged u true i redirekta na početnu stranicu s projektima
                req.session.logged = user._id;
                //HTML response redirect na stranicu s prikazom projekata
                res.format({
                    html: function(){
                        res.redirect("/projects");
                    },
                    //JSON
                    json: function(){
                        res.json(user);
                    }
                });

            } else {
                //ukoliko ne pronađe ispiši not found
                res.send("Not found.");

            }
        });
        });
router.route('/logout')
    .get( function(req, res, next) {
        // uništavanje session objekta
        req.session.destroy(function(err) {
            if(err) {
                return next(err);
            } else {
                return res.redirect('/auth/login');
            }
        });
    });
router.route('/register')
    //prikaz forme za registraciju
    .get( function(req, res, next) {
        res.render('auth/register');
    })
//POST dodavanje novog korisnika
    .post(function(req, res) {
// dohvaćanje vrijednosti iz forme
        var uname = req.body.username;
        var pasw = req.body.password;
        var cpasw = req.body.cpassword;
        //provjera jesu li lozinka i potvrda loznke jednake
        if(pasw!=cpasw){
            res.send('Passwords dont match');
        }
        else {
            //traženje korisnika s unesenim username-om kako bi se spriječili duplikati
            mongoose.model('User').findOne({username:uname}).exec(function (err, user) {
                if (err) {
                    return callback(err)

                } else if (user) {
                    //ukoliko pronađe ispisuje da već postoji error
                    res.send('Already exists');

                } else {
                    //ukoliko ne pronađe može ga kreirati
                    //kreiranje projekta s vrijednostim dohvaćenim iz forme
                    mongoose.model('User').create({
                        username: uname,
                        password: pasw
                    }, function (err, project) {
                        if (err) {
                            res.send("There was a problem adding the information to the database.");
                        } else {
                            //User je kreiran
                            res.format({
                                //HTML response , redirekta na formu za prijavu
                                html: function () {
                                    res.redirect("/auth/login");
                                },
                                //JSON response
                                json: function () {
                                    res.json(project);
                                }
                            });
                        }
                    })
            }
        })
        }
    });

module.exports = router;
