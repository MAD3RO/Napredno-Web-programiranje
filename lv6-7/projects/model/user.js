var mongoose = require('mongoose');
// definiranje scheme za projekt
var userSchema = new mongoose.Schema({
    username: {type: String, index: { unique: true }, required : true},
    password: {type: String, index: { unique: true }, required: true},
});
mongoose.model('User', userSchema);