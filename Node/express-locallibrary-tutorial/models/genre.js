const Schema = mongoose.Schema;

const genre = new Schema({
  name: { type: String, maxLength:100, minLength:3 },
});

// Virtual for bookinstance's URL
BookInstanceSchema.virtual("url").get(function () {
    // We don't use an arrow function as we'll need the this object
    return `/catalog/genre/${this._id}`;
  });