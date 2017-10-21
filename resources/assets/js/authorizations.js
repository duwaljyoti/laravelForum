let user = window.App.loggedUser;

/**
 * @param {{user_id:string}} data
 */

module.exports = {
  owns(model, prop = 'user_id') {
    return model[prop] === user.id;
  }
};