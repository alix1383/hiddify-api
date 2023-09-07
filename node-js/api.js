/*
    Developer Mr_artan
    my telegram https://t.me/mr_saebi
    note* : Be sure to install  axios,moment,dotenv  modules before running
*/

import axios from "axios";
import moment from "moment";
import dotenv from "dotenv";
dotenv.config();

class hiddifyApi {
  constructor() {
    this.adminSecret = process.env.ADMINSECRET;
    this.axios = axios.create({
      baseURL: process.env.MAIN_URL + "/" + process.env.PATH + "/" + process.env.ADMINSECRET,
    });
    this.axios.defaults.headers.common["Accept"] = "application/json";
    this.axios.defaults.headers.post["Content-Type"] = "application/json";
  }

  // generate uuid
  generateuuid() {
    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
      var r = (Math.random() * 16) | 0,
        v = c == "x" ? r : (r & 0x3) | 0x8;
      return v.toString(16);
    });
  }

  //  system
  // check connect to api
  is_connected() {
    return this.axios
      .get("/admin/get_data/")
      .then((res) => {
        if (res.data && typeof res.data === "object") return true;
        return false;
      })
      .catch((err) => console.log(err));
  }

  // get sustem status
  getSystemStatus() {
    return this.axios
      .get("/admin/get_data/")
      .then((res) => {
        return res.data.stats;
      })
      .catch((err) => {
        return err;
      });
  }

  // get user list
  getUserList() {
    return this.axios
      .get("/api/v1/user/")
      .then((res) => {
        return res.data;
      })
      .catch((err) => {
        return err;
      });
  }

  // add servise
  async addServise({ uuid, comment, name, day, traficc, telegram_id }) {
    const data = {
      added_by_uuid: this.adminSecret,
      comment,
      current_usage_GB: 0,
      last_online: null,
      last_reset_time: null,
      mode: "no_reset",
      name,
      package_days: day,
      start_date: moment().format("YYYY-MM-DD"),
      telegram_id,
      usage_limit_GB: traficc,
      uuid,
    };

    return this.axios
      .post("/api/v1/user/", data)
      .then((res) => {
        if (res.data) return uuid;
        return false;
      })
      .catch((err) => {
        return err;
      });
  }

  // find servise
  async findServise(uuid) {
    // get all data
    const { data } = await this.getUserList();
    // search to data
    const userData = data.find((e) => e.uuid == uuid);
    if (!userData) return false;
    this[userData.subData] = this.getDataFromSub(uuid);
    return userData;
  }

  // subset find servise
  getDataFromSub(url) {
    return axios
      .get(url + "/sub/")
      .then((res) => {
        const lines = res.data.split("\n");
        const servers = lines.filter((line) => line.startsWith("vless://") || line.startsWith("trojan://") || line.startsWith("vemss://"));
        return servers;
      })
      .catch((err) => {
        return err;
      });
  }
}

export default hiddifyApi;
