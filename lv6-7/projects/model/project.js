var mongoose = require('mongoose');
// definiranje scheme za projekt
var projectSchema = new mongoose.Schema({
    naziv: String,
    opis: String,
    cijena: Number,
    poslovi: String,
    pdatum: Date,
    zdatum: Date,
    clanovi_tima: [String],
    arhivirano:  { type : Boolean, default: true},
    voditelj_id: String
});
mongoose.model('Project', projectSchema);