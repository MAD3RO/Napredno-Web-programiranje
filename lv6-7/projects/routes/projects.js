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
//middleware koji provjerava je li korisnik ulogiran (provjerava je li postavljena logged varijabla)
function logMid(req, res, next) {
    if (req.session && req.session.logged) {
        return next();
    } else {
        res.redirect("/auth/login");
    }
}
router.route('/arhivirano')
    //prikaz svih projekta na kojima je voditelj ili član, a arhivirani su
    .get([logMid],function(req, res, next) {
        //id trenutnog korisnik iz session varijable
        var id = req.session.logged;
        //dohvaćanje projekata svih projekta na kojima je voditelj ili član, a arhivirani su
        mongoose.model('Project').find({$or: [{voditelj_id:id}, {clanovi_tima:id}],arhivirano:true}, function (err, projects) {
            if (err) {
                return console.error(err);
            } else {
                //ovisno o response formatu vraća se html ili json response
                res.format({
                    //html response renderira view projects/index sa zadanim naslovom i dohvaćenim projektima
                    html: function(){
                        res.render('projects/limitindex', {
                            title: 'Projects',
                            "projects" : projects
                        });
                    },
                    //JSON response prikazuje projekte u json formatu
                    json: function(){
                        res.json(projects);
                    }
                });
            }
        });
    });
router.route('/memberprojects')
//prikaz svih projekta na kojima je član
    .get([logMid],function(req, res, next) {
        //id trenutnog korisnik iz session varijable
        var id = req.session.logged;
        //dohvaćanje iz baze projekata na kojima je član
        mongoose.model('Project').find({clanovi_tima:id}, function (err, projects) {
            if (err) {
                return console.error(err);
            } else {
                //ovisno o response formatu vraća se html ili json response
                res.format({
                    //html response renderira view projects/index sa zadanim naslovom i dohvaćenim projektima
                    html: function(){
                        res.render('projects/limitindex', {
                            title: 'Projects',
                            "projects" : projects
                        });
                    },
                    //JSON response prikazuje projekte u json formatu
                    json: function(){
                        res.json(projects);
                    }
                });
            }
        });
    });
router.route('/')
//prikaz svih projekta na kojima je voditelj
    .get([logMid],function(req, res, next) {
        //id trenutnog korisnik iz session varijable
        var id = req.session.logged;
        //dohvaćanje vlastitih projekata iz baze
        mongoose.model('Project').find({voditelj_id:id}, function (err, projects) {
            if (err) {
                return console.error(err);
            } else {
                //ovisno o response formatu vraća se html ili json response
                res.format({
                    //html response renderira view projects/index sa zadanim naslovom i dohvaćenim projektima
                    html: function(){
                        res.render('projects/index', {
                            title: 'Projects',
                            "projects" : projects
                        });
                    },
                    //JSON response prikazuje projekte u json formatu
                    json: function(){
                        res.json(projects);
                    }
                });
            }
        });
    })
    //POST dodavanje novog projekta
    .post([logMid],function(req, res) {
// dohvaćanje vrijednosti iz forme
        var naziv = req.body.naziv;
        var opis = req.body.opis;
        var cijena = req.body.cijena;
        var poslovi = req.body.poslovi;
        var pdatum = req.body.pdatum;
        var zdatum = req.body.zdatum;
        var voditelj_id = req.session.logged;
        //kreiranje projekta s vrijednostim dohvaćenim iz forme
        mongoose.model('Project').create({
            naziv : naziv,
            opis : opis,
            cijena : cijena,
            poslovi :  poslovi,
            pdatum : pdatum,
            zdatum : zdatum,
            voditelj_id : voditelj_id
        }, function (err, project) {
            if (err) {
                res.send("There was a problem adding the information to the database.");
            } else {
                //Projekt je kreiran
                res.format({
                    //HTML response , redirekta na prikaz svih projekata
                    html: function(){
                        res.redirect("/projects");
                    },
                    //JSON response
                    json: function(){
                        res.json(project);
                    }
                });
            }
        })
    });

//prikaz forme za unos novog projekta
router.get('/new',[logMid], function(req, res) {
    res.render('projects/new', { title: 'Add New Project' });
});

// middleware za validaciju id-a, da se provjeri postoji li projekt s tim id-om
router.param('id', function(req, res, next, id) {

    //dohvaća se iz baze projekt s ID-om iz rute
    mongoose.model('Project').findById(id, function (err, project) {
        if (err) {
            console.log(id + ' was not found');
            res.status(404)
            var err = new Error('Not Found');
            err.status = 404;
            res.format({
                html: function(){
                    next(err);
                },
                json: function(){
                    res.json({message : err.status  + ' ' + err});
                }
            });
            //ukoliko pronađe postavlja request varijablu id u id pronađenog projekta iz rute
        } else {

            req.id = id;
            // idući zahtjev se dohvaća ovisno što je poslje id-a u ruti
            next();
        }
    });
});
//prikaz projekta s id-om iz zahtjeva
router.route('/:id')
    .get([logMid],function(req, res) {
        //pronalazi se projekt po id-u
        mongoose.model('Project').findById(req.id, function (err, project) {
            mongoose.model('User').find({_id:{$in: project.clanovi_tima}}, function (err, clanovi_tima) {
            if (err) {
                console.log('GET Error: There was a problem retrieving: ' + err);
            }
            //ukoliko se pronađe
            else {
               //konverzija datuma u string za prikaz
                var pdatum = project.pdatum.toISOString();
                pdatum = pdatum.substring(0, pdatum.indexOf('T'));
                var zdatum = project.zdatum.toISOString();
                zdatum = zdatum.substring(0, zdatum.indexOf('T'));
                res.format({
                    // html response renderira se show view kojem se šalje pronađeni projekt i konv. datumi, kako bi se prikazale informacije o projektu
                    html: function(){
                        res.render('projects/show', {
                            "pdatum" : pdatum,
                            "zdatum" : zdatum,
                            "project" : project,
                            "clanovi_tima" : clanovi_tima

                        });
                    },
                    json: function(){
                        res.json(project);
                    }
                });
            }
        });
        });
    });
//prikaz informacija  projektu unutar forme za uređivanje
router.route('/:id/edit')

    .get([logMid],function(req, res) {
        //dohvaćanje projekta
        mongoose.model('Project').findById(req.id, function (err, project) {
            mongoose.model('User').find({_id:{$in: project.clanovi_tima}}, function (err, clanovi_tima) {
                mongoose.model('User').find({_id:{$nin: project.clanovi_tima}}, function (err, not_clanovi_tima) {
            if (err) {
                console.log('GET Error: There was a problem retrieving: ' + err);
            } else {
                //konverzija datuma u string za prikaz
                var pdatum = project.pdatum.toISOString();
                pdatum = pdatum.substring(0, pdatum.indexOf('T'));
                var zdatum = project.zdatum.toISOString();
                zdatum = zdatum.substring(0, zdatum.indexOf('T'));
                res.format({
                    //HTML response renderira formu za uređivanje s info o projektu
                    html: function(){
                        res.render('projects/edit', {
                            title: 'Project' + project._id,
                            "pdatum" : pdatum,
                            "zdatum" : zdatum,
                            "project" : project,
                            "clanovi_tima" : clanovi_tima,
                            "not_clanovi_tima" : not_clanovi_tima
                        });
                    },
                    //JSON response
                    json: function(){
                        res.json(project);
                    }
                });
            }
        });
        });
        });
    })
    //PUT request za spremanje podataka koji su uneseni unutar forme za uređivanje
    .put([logMid],function(req, res) {
        // dohvaćanje vrijednosti iz forme
        var naziv = req.body.naziv;
        var opis = req.body.opis;
        var cijena = req.body.cijena;
        var poslovi = req.body.poslovi;
        var pdatum = req.body.pdatum;
        var zdatum = req.body.zdatum;
        var arhivirano = req.body.arhivirano ? true : false;

        //pronalazak projekta po ID-u
        mongoose.model('Project').findById(req.id, function (err, project) {
            //ažuriranje pronađenog projekta s novim vrijednostima dohvaćenim iz forme
            project.update({
                naziv : naziv,
                opis : opis,
                cijena : cijena,
                poslovi :  poslovi,
                pdatum : pdatum,
                zdatum : zdatum,
                arhivirano : arhivirano
            }, function (err, projectID) {
                if (err) {
                    res.send("There was a problem updating the information to the database: " + err);
                }
                else {
                    //HTML response redirect na stranicu s prikazom trenutno uređenog projekta
                    res.format({
                        html: function(){
                            res.redirect("/projects/" + project._id);
                        },
                        //JSON
                        json: function(){
                            res.json(project);
                        }
                    });
                }
            })
        });
    })
    //brisanje Projekta po ID-u
    .delete([logMid],function (req, res){
        //pronalazak projekta po id-u
        mongoose.model('Project').findById(req.id, function (err, project) {
            if (err) {
                return console.error(err);
            } else {
                //ukoliko je pronađen projekt se briše
                project.remove(function (err, project) {
                    if (err) {
                        return console.error(err);
                    } else {
                        res.format({
                            //HTML redirekta na prikaz liste svih projekata
                            html: function(){
                                res.redirect("/projects");
                            },
                            //JSON
                            json: function(){
                                res.json({message : 'deleted',
                                    item : project
                                });
                            }
                        });
                    }
                });
            }
        });
    });
router.route('/:id/addmember')
//PUT request za spremanje podataka koji su uneseni unutar forme za dodavanje novog člana
    .put([logMid],function(req, res) {
        // dohvaćanje vrijednosti iz forme
        var clan = req.body.new_clan_tima;
        //pronalazak projekta po ID-u
        mongoose.model('Project').findById(req.id, function (err, project) {
            //dodavanje novog clana u string trenutnih clanova
            var clanovi = project.clanovi_tima;
            clanovi.push(clan);
            //ažuriranje pronađenog projekta s novim stringom članova
            project.update({
                clanovi_tima : clanovi,
            }, function (err, projectID) {
                if (err) {
                    res.send("There was a problem updating the information to the database: " + err);
                }
                else {
                    //HTML response redirect na stranicu s prikazom trenutno uređenog projekta
                    res.format({
                        html: function(){
                            res.redirect("/projects/" + project._id);
                        },
                        //JSON
                        json: function(){
                            res.json(project);
                        }
                    });
                }
            })
        });
    });
//ogranično uređivanje članova koji mogu mjenjati samo obavljene poslove
router.route('/:id/limitedit')
    .get([logMid],function(req, res) {
        //dohvaćanje projekta
        mongoose.model('Project').findById(req.id, function (err, project) {
            mongoose.model('User').find({_id:{$in: project.clanovi_tima}}, function (err, clanovi_tima) {
                    if (err) {
                        console.log('GET Error: There was a problem retrieving: ' + err);
                    } else {
                        //konverzija datuma u string za prikaz
                        var pdatum = project.pdatum.toISOString();
                        pdatum = pdatum.substring(0, pdatum.indexOf('T'));
                        var zdatum = project.zdatum.toISOString();
                        zdatum = zdatum.substring(0, zdatum.indexOf('T'));
                        res.format({
                            //HTML response renderira formu za uređivanje s info o projektu
                            html: function(){
                                res.render('projects/limitedit', {
                                    title: 'Project' + project._id,
                                    "pdatum" : pdatum,
                                    "zdatum" : zdatum,
                                    "project" : project,
                                    "clanovi_tima" : clanovi_tima
                                });
                            },
                            //JSON response
                            json: function(){
                                res.json(project);
                            }
                        });
                    }
                });
            });
    })
//PUT request za spremanje obavljenih poslova koji su uneseni unutar forme za uređivanje
    .put([logMid],function(req, res) {
        // dohvaćanje vrijednosti iz forme
        var poslovi = req.body.poslovi;

        //pronalazak projekta po ID-u
        mongoose.model('Project').findById(req.id, function (err, project) {
            //ažuriranje pronađenog projekta s novim vrijednostima dohvaćenim iz forme
            project.update({
                poslovi :  poslovi,
            }, function (err, projectID) {
                if (err) {
                    res.send("There was a problem updating the information to the database: " + err);
                }
                else {
                    //HTML response redirect na stranicu s prikazom trenutno uređenog projekta
                    res.format({
                        html: function(){
                            res.redirect("/projects/" + project._id);
                        },
                        //JSON
                        json: function(){
                            res.json(project);
                        }
                    });
                }
            })
        });
    });
module.exports = router;