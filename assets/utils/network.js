import axios from 'axios';
import Cookies from 'js-cookie';
import BuildUrl from 'build-url';

export default {
  token() {
    const token = Cookies.get('JWT_TOKEN');
    if (token) {
      return `Bearer ${token}`;
    }
    return false;
  },
  async get(url, auth = false, token = auth ? this.token() : null) {
    const config = {};

    if (auth && token) {
      config.headers = {
        Authorization: token
      };
    }

    return await axios
      .get(url, config)
      .then(response => (response && response.data ? response.data : response))
      .catch(({response}) => {
        if (response) {
          if (response.data) {
            return response.data;
          }
          return response;
        }
        return false;
      });
  },
  async getHydraMembers(
    auth = false,
    _path,
    params,
    page = 1,
    itemsPerPage = 18
  ) {
    if (params.pagination !== 'false') {
      if (!params.page) {
        params.page = page;
      }
      if (!params.itemsPerPage) {
        params.itemsPerPage = itemsPerPage;
      }
    }
    const url = this.buildPath(_path, params);
    const response = await this.get(url, auth, auth ? this.token() : null);
    if (response && response['hydra:member']) {
      return response['hydra:member'];
    }
    return false;
  },
  async post(url, form, auth = true) {
    const config = {};

    if (auth) {
      const token = this.token();

      if (token) {
        config.headers = {
          Authorization: token
        };
      }
    }

    return await axios
      .post(url, form, config)
      .then(response => response)
      .catch(({response}) => response);
  },
  async delete(url, auth = true) {
    const config = {};

    if (auth) {
      const token = this.token();

      if (token) {
        config.headers = {
          Authorization: token
        };
      }
    }

    return await axios
      .delete(url, config)
      .then(response => response)
      .catch(({response}) => response);
  },
  async put(url, data = {}, auth = true) {
    const config = {};

    if (auth) {
      const token = this.token();

      if (token) {
        config.headers = {
          Authorization: token
        };
      }
    }

    return await axios
      .put(url, data, config)
      .then(response => response)
      .catch(({response}) => response);
  },
  buildPath(_path, params = {}) {
    return BuildUrl('', {
      path: _path,
      queryParams: params
    });
  }
};
