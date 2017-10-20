let user = window.App.loggedUser;

/**
 * @param {{user_id:string}} data
 */

module.exports = {
  updateReply(reply) {
    return user.id === reply.user_id;
  }
};